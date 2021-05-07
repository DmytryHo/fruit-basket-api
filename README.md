## Configuration

Install dependencies:
```sh
composer install --ignore-platform-reqs
```

Install roadrunner:
```sh
vendor/bin/rr get --location bin/
```

Start MySQL and Api servers in docker (on 13340 port, by default)
```sh
docker-compose up
```

Create MySQL schema by running command in php-container:
```sh
php bin/console doctrine:migration:migrate
```

##Request Examples:
Create basket:
```sh
curl --request POST \
  --url http://localhost:13340/baskets \
  --header 'Content-Type: application/json' \
  --data '{
	"name":"New Basket",
	"maxCapacity": 12.11
}'
```


Update basket:
```sh
curl --request PATCH \
  --url http://localhost:13340/baskets/5 \
  --header 'Content-Type: application/json' \
  --data '{
	"name":"New Basket Edited",
	"maxCapacity":13.3
}'
```

Get single Basket info:
```sh
curl --request GET \
  --url http://localhost:13340/baskets/5 \
  --header 'Content-Type: application/json'
```

Get all Baskets info:
```sh
curl --request GET \
  --url http://localhost:13340/baskets \
  --header 'Content-Type: application/json'
```

Delete Basket:
```sh
curl --request DELETE \
  --url http://localhost:13340/baskets/3 \
  --header 'Content-Type: application/json'
```

Add items to Basket:
```sh
curl --request POST \
  --url http://localhost:13340/baskets/1/items \
  --header 'Content-Type: application/json' \
  --data '[
	{
		"type": "apple",
		"weight": 0.15
	},
	{
		"type": "apple",
		"weight": 0.2
	}
]'
```

Remove All items from Basket:
```sh
curl --request DELETE \
  --url http://localhost:13340/baskets/1/items \
  --header 'Content-Type: application/json'
```