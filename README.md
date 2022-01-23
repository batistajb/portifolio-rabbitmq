# Project Postter

The project was developed to selective process, see below the steps to run project and time budget used.

### Time budget

1. Study, analysis and construction of the infrastructure strategy -> 6 hours
2. Develop to back-end in Laravel(php) -> 5 hours
3. Develop to back-end in Loopback(nodejs) -> 3,5 hours
4. Document to api -> 1,5 hours

### Prepare environment

To run project you just need install [docker](https://docs.docker.com/get-docker/) and [docker-compose](https://docs.docker.com/compose/install/). After install docker, you need to guarantee that the user docker has permission to execute the project.

#### Run project

Remember to check if the ports of services is available.

* 8080      -> back-end laravel
* 3000      -> back-end loopback
* 6379      -> cache redis
* 3306      -> database mysql
* 9200/9300 -> database elasticsearch
* 5601      -> view elasticsearch kibana
* 5672      -> queue rabbitmq
* 15672     -> manager rabbitmq

Open terminal/cmd in project root folder and execute:

`docker-compose up -d --build`

Check is all services is runing with command:

`docker ps`

Should return:


| Service Name       | Image                                               | Port                |
| --------------------- | ----------------------------------------------------- | --------------------- |
| postter-laravel-api | postter-laravel-api                                 | 9000/tcp            |
| postter-posts-api   | posterr-api_postter-posts-api                       | 3000/tcp            |
| postter-cache       | redis:latest                                        | 6379/tcp            |
| kibana_posterr      | docker.elastic.co/kibana/kibana:7.9.3               | 5601/tcp            |
| es_data_posterr     | docker.elastic.co/elasticsearch/elasticsearch:7.9.3 | 9200/tcp, 9300/tcp  |
| postter-database    | mysql:5.7                                           | 3306/tcp            |
| posterr-rabbitmq    | rabbitmq:3.8-management-alpine                      | 4369/tcp, 5671/tcp, |
| postter-nginx       | postter-nginx                                       | 8080/tcp            |

If not run all services run separate:

`docker-compose up -d {{service_name}}`

### Application Strategy

The laravel was used to manager every application, but at the same time it synchronizes the database mysql with elasticsearh. The justify is that a database Nosql has better performace in searchs/queries.

##### Laravel

Responsible by manager app, it run schedule/job to capture likes of posts e save in mysql database. The redis was used in much endpoints to relieve direct requests to the database.

Roadmap:

* Make tests
* Abstract service of posts by cache-redis
* Authenticate User to list posts, followins, followers, likes and all resources

##### Elasticseach

Database Nosql has better performace to queries.

##### Loopback

Synergy with elasticsearch and skilled to work with API/microservices.

Roadmap:

* Make tests
* Sync follows
* Configure follows relationship with resource of posts

##### Rabbitmq

Queue architecture, was used to synchronize events the laravel with loopback

### Back-End Laravel

1. Install libs composer:

`docker exec postter-laravel-api composer install`

`docker exec postter-laravel-api php artisan config:clear`

`docker exec postter-laravel-api php artisan key:generate`

`docker exec postter-laravel-api php artisan migrate`

`docker exec postter-laravel-api php artisan db:seed`

2. Run watch Jobs:

`docker exec postter-laravel-api php artisan schedule:work`

#### Routes - Endpoints

All routes have prefix "localhost:8080/api/"

##### posts/

Routes of posts

###### GET /api/posts

List all posts

###### GET /api/posts/?params

List posts base params in url

###### POST /api/posts/

Store post

```json
"url" => "required",
"type" => "required",
"user_id" => "required",
"content" => "required"
```

###### GET /api/posts/'uuid'

Get one post base uuid

###### PUT /api/posts/'uuid'

Update one post base uuid

###### DELETE /api/posts/'uuid'

Delete one post base uuid

####### GET /api/posts/follows/'uuid'
Get all posts from users I follow

###### GET /api/posts/followers/'uuid'

Get all posts from users who follow me

###### GET /api/posts/my/'uuid'

Get all my posts

###### POST /api/posts/like/'post_id'

Like in post

###### POST /api/posts/deslike/'post_id'

Deslike in post

##### /profile/

Routes of profile

###### GET /api/profile

Get all users

###### GET /api/profile/'uuid'

Get one user by uuid

###### GET /api/follows/'user_uuid'

Get all profiles I follow

###### GET /api/followers/'user_uuid'

Get all profiles that follow me

###### POST /api/follow/'user_uuid'

Follow profile

###### POST /api/unfollow/'user_uuid'

Unfollow profile

### Back-End Loopback

Access [localhost:3000](https://localhost:3000),you can see /openapi.json and /explorer to document api.

Basically the intention is centralize the searchs of posts this service, becou
