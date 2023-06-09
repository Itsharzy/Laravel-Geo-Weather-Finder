## How to setup this project in your enviroment.

Using docker you will be able to "docker-compose up -d" to generate your new container this should include everything you need to ensure things run smoothly.

If you're having any styling issues run npm run build against the repo.

If your PHP service has stopping due to it being unable to find "entrypoint.sh" ensure that you have cleared your docker cache and if th issue still persists recreate the file with the same code and rebuild the project.

