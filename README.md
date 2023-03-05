## API Documentation
### Requirement
 - Latest docker engine
 - Latest docker-compose
### Run the apps
1. Make sure that you're in root directory of this repo.
2. Create new docker network with the name of "public" (this name reference to `docker-compose.yml`)
   ```
   docker network create public
   ```

3. Deploy the docker-compose using this command.
   ```
   docker-compose -f docker/docker-compose.yml --env-file docker/.env.example up -d
   ```
4. You need to make that all the containers are running before executing next step.
5. Get running container names by using this command.
   ```
   docker ps
   ```

6. Migrate table on a running container using this command. There are 3 running container by default (api-worker, query-api, command-api).
   ```
   docker exec {container-name} bash -c "php artisan config:clear && php artisan config:cache && php artisan migrate"
   ```
7. Start supervisord jobs named "sync-record". You can start it from the supervisor web panel that accessible using port 9001.

### Ports
- Command API: 8031
- Query API: 8032
- Supervisord: 9001
- RabbitMQ: 15672

### Common Problem
- Supervisord is not started (need to start from the container)
- Database container is not started (need to start to container)
