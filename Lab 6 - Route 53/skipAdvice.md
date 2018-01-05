### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

If you created the following services already, please remove them first:
    * a route53 record. 

1. You should be logged in to your Management Instance.
1. `export MYID=<your_ID> && export MYREGION=eu-west-1`
    * Set environment variables.
1. `export DNSName=$(aws elb describe-load-balancers --region $MYREGION | jq ".LoadBalancerDescriptions[] | select(.LoadBalancerName==\"lab-ELB-$MYID\") | .DNSName" | head -n 1 | tr -d '"')`
    * Get load balancer DNS name.
1. `export hostedzone=$(aws route53 list-hosted-zones | jq ".HostedZones[] | select(.Name==\"gluo.cloud.\") | .Id" | head -n 1 | tr -d '"/hostedzone/')`
    * Get hosted zone.
1. ```
echo -e "{
    \"Comment\": \"Update or insert memegen load balancer record\",
    \"Changes\": [
        {
            \"Action\": \"UPSERT\",
            \"ResourceRecordSet\": {
                \"Name\": \"$MYID.gluo.cloud.\",
                \"Type\": \"CNAME\",
                \"TTL\": 300,
                \"ResourceRecords\": [
                    {
                        \"Value\": \"$DNSName\"
                    }
                ]
            }
        }
    ]
}" > route53Record.json
```
    * Create route53 json file.
1. `aws route53 change-resource-record-sets --hosted-zone-id $hostedzone --change-batch file://route53Record.json`
    * Create Route53 record.