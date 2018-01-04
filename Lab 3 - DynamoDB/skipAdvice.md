### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

1. You should be logged in to your own created instance.
1. `export MYID=<your_ID> && export MYREGION=eu-west-1`
    * Set environment variables.
1. `aws dynamodb create-table --table-name images-$MYID --attribute-definitions AttributeName=id,AttributeType=N --key-schema AttributeName=id,KeyType=HASH --provisioned-throughput ReadCapacityUnits=5,WriteCapacityUnits=5 --region $MYREGION`
    * Create a DynamoDB table.
1. ```
echo -e "{
    \"images-$MYID\": [
        {
            \"PutRequest\": {
                \"Item\": {
                    \"id\": {\"N\": \"1\"},
                    \"name\": {\"S\": \"successkid\"},
                    \"date\": {\"S\": \"1\"}
                }
            }
        },
        {
            \"PutRequest\": {
                \"Item\": {
                    \"id\": {\"N\": \"2\"},
                    \"name\": {\"S\": \"badluckbrian\"},
                    \"date\": {\"S\": \"2\"}
                }
            }
        },
        {
            \"PutRequest\": {
                \"Item\": {
                    \"id\": {\"N\": \"3\"},
                    \"name\": {\"S\": \"willywonka\"},
                    \"date\": {\"S\": \"3\"}
                }
            }
        },
        {
            \"PutRequest\": {
                \"Item\": {
                    \"id\": {\"N\": \"4\"},
                    \"name\": {\"S\": \"archer\"},
                    \"date\": {\"S\": \"4\"}
                }
            }
        },
        {
            \"PutRequest\": {
                \"Item\": {
                    \"id\": {\"N\": \"5\"},
                    \"name\": {\"S\": \"yodawg\"},
                    \"date\": {\"S\": \"5\"}
                }
            }
        }
    ]
}" > DynamoDBRecords.json
```
    * Add json to a file.
1. `aws dynamodb batch-write-item --request-items file://DynamoDBRecords.json --region $MYREGION`
    * Add records to the database.
1. `sed -i 's@^$remoteData.*@$remoteData = true; # DynamoDB (Altered by sed)@g' /var/www/html/config.php`
    * Change the $remoteData variable to true to enable DynamoDB for MemeGen.
1. Go to the site and create a meme.
1. Go to `Services -> DynamoDB -> images-<your_ID>` to verify a record was added.