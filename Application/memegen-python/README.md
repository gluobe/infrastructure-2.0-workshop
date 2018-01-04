Memegen
=======

This application folder houses the Gluo Python Meme Generator.

Features
========
* New Image Template Upload
* Creates static links for all memes generated
* The generated memes can be uploaded to a S3 bucket.
* The parameters of a generated meme can be saved in an AWS DynamoDB table or in a MongoDb table.

How It Works
============

Memegen stores all images on the local file system in the static/images and static/memes folders.  memegen.db is a sqlite3 database that keeps track of all the files in these folders. The generated meme can be uploaded to a S3 Bucket. The student can choose between a DyanomoDB table or MongoDB table.

* Memegen uses PIL (Python Image Library) to write text on images.
* Memegen uses Python-flask to generate a Web-environment on apache. It uses Jinja2 to generate the HTML files.
* Memegen uses boto3 that makes use of Amazon services like S3 and DynamoDB.

Files
============

* ´config.py´ is the file where a student can change the site color, the name of the S3 bucket or the name of the table in DynamoDB.

Possible Improvements 
============

*  Completely replace sqlite with DynamoDB/S3.