if [ -n "$ZSH_VERSION" ]; then emulate -L ksh; fi
######################################################
# fleet sanitization demo script, using the CLI.
#
# Enables the following workflow on a given project:
# .
# └── main
#     ├── staging
#     |   └── new-feature
#     └── auto-updates
#
# Usage
# 1. source this script: `. fleet_sanitizer.sh` or `source fleet_sanitizer.sh`
# 2. define ORGANIZATION var: ORGANIZATION=sfcon2022
# 3. run `sanitize_organization_data $ORGANIZATION`
######################################################

# Utility functions, used throughout examples.

# list_org_projects: Print list of projects operation will be applied to before starting.
#   $1: Organization, as it appears in console.platform.sh.
list_org_projects () {
    platform project:list -o $1 --columns="ID, Title"
}

# get_org_projects: Retrieve an array of project IDs for a given organization.
#   Note: Makes array variable PROJECTS available to subsequent scripts.
#   $1: Organization, as it appears in console.platform.sh.
get_org_projects () {
    PROJECTS_LIST=$(platform project:list -o $1 --pipe)
    PROJECTS=($PROJECTS_LIST)
}

# get_org_projects: Retrieve an array of envs IDs for a project.
#   Note: Makes array variable ENVS available to subsequent scripts.
#   $1: ProjectId, as it appears in console.platform.sh.
get_project_envs () {
    ENV_LIST=$(platform environment:list -p $1 --pipe)
    ENVS=($ENV_LIST)
}

# list_project_envs: Print list of envs operation will be applied to before starting.
#   $1: ProjectId, as it appears in console.platform.sh.
list_project_envs () {
    platform environment:list -p $1
}

# add_env_var: Add environment level environment variable.
#   $1: Variable name.
#   $2: Variable value.
#   $3: Target project ID.
#   $4: Target environment ID.
add_env_var () {
    VAR_STATUS=$(platform project:curl -p $3 /environments/$4/variables/env:$1 | jq '.status')
    if [ "$VAR_STATUS" != "null" ]; then
        platform variable:create \
            --name $1 \
            --value "$2" \
            --prefix env: \
            --project $3 \
            --environment $4 \
            --level environment \
            --json false \
            --sensitive false \
            --visible-build true \
            --visible-runtime true \
            --enabled true \
            --inheritable true \
            -q
    else
        printf "\nVariable $1 already exists. Skipping."
    fi
}

sanitize_organization_data () {
    list_org_projects $1
    get_org_projects $1
    for PROJECT in "${PROJECTS[@]}"
    do
        printf "\n### Project $PROJECT."
        # get environments list
        list_project_envs $PROJECT
        get_project_envs $PROJECT
        for ENVIRONMENT in "${ENVS[@]}"
        do
          unset -f ENV_CHECK
          ENV_CHECK=$(platform project:curl -p $PROJECT /environments/$ENVIRONMENT | jq -r '.status')
          unset -f ENV_TYPE
          ENV_TYPE=$(platform project:curl -p $PROJECT /environments/$ENVIRONMENT | jq -r '.type')

          if [ "$ENV_CHECK" = active -a "$ENV_TYPE" != production ]; then
              unset -f DATA_SANITIZED
              DATA_SANITIZED=$(platform variable:get -p $PROJECT -e $ENVIRONMENT env:DATA_SANITIZED --property=value)
              if [ "$DATA_SANITIZED" != true ]; then
                printf "\nEnvironment $ENVIRONMENT exists and is not sanitized yet. Sanitizing data."
                printf "\n"
                # do sanitization here
                platform ssh -p $PROJECT -e $ENVIRONMENT -- php bin/console app:sanitize-data
                printf "\nSanitizing data is finished, redeploying"
                add_env_var DATA_SANITIZED true $PROJECT $ENVIRONMENT
              else
                printf "\nEnvironment $ENVIRONMENT exists and does not need to be sanitized. skipping."
              fi
          elif [ "$ENVIRONMENT" == main ]; then
                printf "\nEnvironment $ENVIRONMENT is production one, skipping."
          else
                printf "\nEnvironment $ENVIRONMENT is not active $ENV_CHECK, skipping."
          fi
        done
    done
}