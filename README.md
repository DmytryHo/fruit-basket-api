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

## Request Examples:
<details>
<summary>Create new basket</summary>
URI for new Basket will be placed into 'Location' response header

```sh
curl --request POST \
  --url http://localhost:13340/baskets \
  --header 'Content-Type: application/json' \
  --data '{
    "name":"New Basket",
    "maxCapacity": 12.11
}'
```
</details>

<details>
<summary>Update basket</summary>

```sh
curl --request PATCH \
  --url http://localhost:13340/baskets/5 \
  --header 'Content-Type: application/json' \
  --data '{
	"name":"New Basket Edited",
	"maxCapacity":13.3
}'
```
</details>

<details>
<summary>Get Basket info</summary>

```sh
curl --request GET \
  --url http://localhost:13340/baskets/5 \
  --header 'Content-Type: application/json'
```
</details>

<details>
<summary>Get all Baskets</summary>

```sh
curl --request GET \
  --url http://localhost:13340/baskets \
  --header 'Content-Type: application/json'
```
</details>

<details>
<summary>Add items to Basket</summary>

Delete Basket:
```sh
curl --request DELETE \
  --url http://localhost:13340/baskets/3 \
  --header 'Content-Type: application/json'
```
</details>

<details>
<summary>Add items to Basket</summary>

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
</details>

<details>
<summary>Remove All items from Basket</summary>

```sh
curl --request DELETE \
  --url http://localhost:13340/baskets/1/items \
  --header 'Content-Type: application/json'
```
</details>