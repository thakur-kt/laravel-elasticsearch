

Install Elasticsearch via Docker:
docker run -d --name elasticsearch -p 9200:9200 -e "discovery.type=single-node" elasticsearch:8.13.0

Install Kibana:
docker run -d --name kibana -p 5601:5601 --link elasticsearch:kibana elastic/kibana:8.13.0

Test in browser:

http://localhost:9200

http://localhost:5601
