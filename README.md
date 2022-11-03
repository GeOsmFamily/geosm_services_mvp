## GeOsm_Services

New Services Laravel for project GeOsm

## Installation

```sh
$ git clone https://github.com/GeOsmFamily/geosm_services_mvp.git
$ cd geosm_services_mvp
$ cp .env.example .env
```

-   edit & add DB & Email infos in .env

```
DB_DATABASE=database name
DB_USERNAME=database username
DB_PASSWORD=database password

MAIL_MAILER=smtp
MAIL_HOST=your host
MAIL_PORT=your port
MAIL_USERNAME=your username
MAIL_PASSWORD=your password
MAIL_ENCRYPTION=TLS
MAIL_FROM_ADDRESS=infos@position.cm
MAIL_FROM_NAME=Position

FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=
FACEBOOK_REDIRECT=

GOOGLE_APP_ID=
GOOGLE_APP_SECRET=
GOOGLE_REDIRECT=

LINKEDIN_APP_ID=
LINKEDIN_APP_SECRET=
LINKEDIN_REDIRECT=

OSM_APP_ID=
OSM_APP_SECRET=
OSM_REDIRECT=

INTERSECTION=false
URL_QGIS=
CARTO_URL=

INSTANCE_NAME=

SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=
MEILISEARCH_KEY=


```

-   edit & add Docker config in .env

```
APP_PORT=
FORWARD_DB_PORT=
PG_PASSWORD=
MEILI_PORT=
```

```
$ docker-compose up -d
$ docker exec -it geoportail_abidjan bash
$ composer install
```

```
$ php artisan key:generate
$ php artisan migrate
$ php artisan passport:install
$ php artisan db:seed
$ php artisan apikey:generate app1
$ php artisan storage:link
$ php artisan scribe:generate
$ exit
```

-   Add authorization in docker

```
go to services folder
$ chown -R www-data:www-data *
```

-   change geom type in database to geometry

-   upload shp in laravel folder

-   execute command and change params

```
$ docker exec -it geoportail_abidjan bash
$ ogr2ogr -f "PostgreSQL" PG:"host=geoportail_abidjan_pgsql user=postgres dbname=abidjan password=postgres" shp/abidjan.shp   -nln temp_table -nlt MULTIPOLYGON  -lco GEOMETRY_NAME=geom -lco precision=NO
```

-   go to pgadmin and execute query

```
UPDATE instances SET geom = ST_Buffer(st_transform(limite.geom ,4326)::geography,10)::geometry FROM (SELECT * from temp_table limit 1) as limite WHERE instances.id = 1;

TRUNCATE temp_table;
```

-   install osm2pgsql

-   create osm folder in laravel folder and add default.style file

-   load osm data

```
$ osm2pgsql --cache 10000 --number-processes 5 --extra-attributes --slim -G -c -U postgres -d $db -H localhost -W --hstore-all -S osm/default.style https://download.geofabrik.de/africa/mali-latest.osm.pbf
```

## Documentation

### Allowed verbs

`GET`, `POST`, `PUT`, `PATCH` ou `DELETE`

### Required in the header of all requests

```
Content-Type: application/json
Accept: application/json
X-Authorization : yourApiKey
```

-   Documentation Link : https://prrojectUrl/docs
