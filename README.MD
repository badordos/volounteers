System requirements:
- FFMPEG and FFPROBE. On Ubuntu simply run `apt install ffmpeg -y`. On Windows you must download binaries and set paths to them in your .env (FFMPEG_BINARIES and FFPROBE_BINARIES)
- If you want to queue your notifications then you need to create queues on the server and change corresponding setting in your .env file (QUEUE_DRIVER) 