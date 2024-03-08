To run this project locally, you need DDEV (and so, Docker) and then:

```bash
ddev start
ddev composer install 
ddev npm run dev 
ddev sf d:s:u --dump-sql --force
ddev sf d:f:l -e dev
```

Then open https://pink-rabbit.ddev.site:4432/landing and follow the pink rabbit
