To run this project locally, you need DDEV (and so, Docker) and then:

```bash
ddev start
ddev ssh
composer install 
npm run dev 
php bin/console d:s:u --dump-sql --force
php bin/console d:f:l -e dev
exit
```

Then open https://pink-rabbit.ddev.site:4432/landing and follow the pink rabbit
