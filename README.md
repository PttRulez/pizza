Примерно так должно заработать =)

```
git clone git@github.com:PttRulez/pizza.git
cd pizza
composer install
cp .env.example .env
sail artisan key:generate
sail up -d
sail artisan migrate
sail artisan db:seed
sail test
```