SolidGear Bulletin
======

Every Monday, we send our newsletter subscribers a free round-up of latest technology trend news and must-reads. It's a curated digest that enables you to stay updated while saving time.

It's compiled with suggestions from SolidGear team members.

Build docker image to work in
---------------

* Clone repo
* Change directory to docker
* Build docker image

    ```docker build -t bulletin .```
* Run docker image

    ```docker run -t -i -d -v /local/source/folder:/opt/bulletin bulletin```
* Inside docker image

    ```docker exec -t -i bulletin bash -l```
* Generate bulletin. ALWAYS use absolute paths

    ```cd /opt/bulletin && ./generate_bulletin.php /opt/bulletin/week-01-2016/ http://solidgear.es/bulletin-images/012016 true```
* SCP /opt/bulletin/week-01-2016/ to solidgear public website, because thumbnails must be in a webserver.

    ```
    http://solidgear.es/bulletin-images/012016
    ```
    
    
**NOTE: This is a very preliminary MVP project**
