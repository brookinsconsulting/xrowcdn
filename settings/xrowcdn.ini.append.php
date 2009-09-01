<?php /*

[Settings]
AWSKey=AWSKey
SecretKey=ThisIsTheSecretKey

ImplementationAlias[]
ImplementationAlias[xrowCloundFront]=xrowCloundFront

Directories[]
#Directories[]=var/cache/public
Directories[]=var
Directories[]=extension
Directories[]=design
Directories[]=share/icons

DirectoriesDistribution[]
DirectoriesDistribution[]=var/cache/public
DirectoriesDistribution[]=extension
DirectoriesDistribution[]=design
DirectoriesDistribution[]=share/icons

[Rules]
List[]
List[]=distribution
List[]=database
List[]=js

[Rule-distribution]
Dirs[]
#Dirs[]=\/(extension|design|var)(\/[a-z0-9_-]+)*\/(images|public|packages)
Dirs[]=\/extension\/[a-z0-9_-]+\/design\/[a-z0-9_-]+\/(images|stylesheets)
Dirs[]=\/design\/[a-z0-9_-]+\/(images|stylesheets)
Dirs[]=\/var\/storage\/packages
Dirs[]=\/var\/[a-z0-9_-]+\/cache\/public
Suffixes[]
Suffixes[]=gif
Suffixes[]=jpg
Suffixes[]=jpeg
Suffixes[]=png
Suffixes[]=ico
Suffixes[]=css
Bucket=MyBucket
Replacement=http://distribution.statix.example.com

[Rule-database]
Dirs[]
Dirs[]=\/var\/[a-z0-9_-]+\/storage\/images
Suffixes[]
Suffixes[]=gif
Suffixes[]=jpg
Suffixes[]=jpeg
Suffixes[]=png
Bucket=MyBucket
Replacement=http://images.statix.example.com

[Rule-js]
Dirs[]
#Dirs[]=\/(extension|design|var)(\/[a-z0-9_-]+)*\/(javascript|public|packages)
Dirs[]=\/extension\/[a-z0-9_-]+\/design\/[a-z0-9_-]+\/javascript
Dirs[]=\/design\/[a-z0-9_-]+\/javascript
Dirs[]=\/var\/[a-z0-9_-]+\/cache\/public
Suffixes[]
Suffixes[]=js
Bucket=MyBucket
Replacement=http://js.statix.example.com

*/ ?>