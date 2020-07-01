# BiM Film Directory API
> A REST API for the Bricks in Motion film directory

## Brief

[Bricks in Motion](https://www.bricksinmotion.com/) is a friendly filmmaking community devoted to the art of stop-motion animation. [Since 2009](https://www.bricksinmotion.com/forums/post/45237/), BiM has kept a film directory where brickfilm animators can submit their films. However, after running for over 10 years, the directory has become a logical mess and a nightmare to maintain and bug fix, yet remains popular and receives film submissions daily.

In late 2018, the BiM leadership expressed a renewed desire to migrate the forum from a PunBB-based site to something more modern. Unfortunately, due to deep technical integration with the PunBB forum software, it is impossible to run the directory independently or migrate to a different platform. Originally, the directory was planned to be archived preserved for posterity. A brand new, read-only directory [was developed](https://github.com/BricksInMotion/film-directory) to accomplish just that, but was later rejected from growing support for keeping the directory running.

To that end, the purpose of this this API to is to provide an independent, simplified way to access the directory contents regardless of forum software platform. The API builds on work performed in the original directory archive project while initially keeping the original database structure.

## Requirements

1. PHP 7.4+
1. MySQL/MariaDB credentials

## Usage

1. `$ cp .login/credentials-example.json .login/credentials.json`
1. Add database credentials

## License

[MIT](LICENSE)

2020 Bricks in Motion
