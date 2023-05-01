# Feed It Back Technical Test

## Setup

Ensure you have Docker installed. If you are using windows be se aware you will need to enable [WSL2](https://laravel.com/docs/9.x/installation#getting-started-on-windows)

Start by referring to the [Docs](https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects) for steps on how to install dependencies for existing applications.

Add an [alias](https://laravel.com/docs/9.x/sail#configuring-a-bash-alias) for sail .

Then run the migration and seeder to populate your database with genres:

- `sail up -d`
- `sail artisan migrate`
- `sail artisan db:seed`

## The task

You have been tasked with creating an API for a Movie Database website. A user will be able to submit a form to add a movie as well as viewing a list of all movies with a few filters available.
We have provided a starting point, including some models, factories, migrations and general guidance has been added as comments in the controller.

A movie will contain the following data:

- Name 
- User ID
- Synopsis
- Image URL
- Release Date (Y-m-d)
- Rating (U, PG, 12a, 12, 15, 18)
- Award winning (bool)

Additionally a movie can also be associated with genres and actors. There is a need in the future for genres to be able to be associated with other entities e.g Tv Shows/Music/Books/Games. You should architect a solution for this realtionship with this in mind. 

The following genres have been provided via a seeder:

- Comedy
- Action
- Horror
- Sci-fi
- Romance
- Drama

Users may associate as many genres/actors with a movie as they like.

Users will be able to filter movies by their release date as well as genres and actors. When filtering by genres and actors at the same time OR functionality should be applied. All of the filters are optional.

## Recommended steps

- Start by outlining some feature tests to facilitate TDD
- Decide on the relevant relationships needed 
- Write migrations for the tables to facilitate those relationships
- Add the relationships to the movie model. 
- Update the factory to use the relationships and seed 5000 movies in to the DB
- Build endpoints
- Write feature tests.
 
## Things to consider

### Responses

- We use JsonResource classes to return responses from our API, you should build your endpoints to do the same

### Relationships / Database design

- Table structure for relationship. Keep in mind genres will need to support multiple different models in the future.

### Tests

- Robust feature tests, which cover submitting invalid data to test validation and assert correct responses returned from the API
- We use [Pest PHP](https://pestphp.com/docs/installation) for our tests, we suggest you do the same. We have required pest in the project and setup a starting point for you.
- Use `sail composer pest` to run your tests.

### General

- Performance, avoid performing unnecessary queries.

## Bonus Points

If you want to go above and beyond you can extend the endpoints to allow a user to update/delete movies which they uploaded. Alternatively you can provide notes on how you would implement this.

## Thank you

Thank you for taking the time to review our technical test specification. If you have any questions please contact devsupport@feeditback.com. Good luck with your application!
