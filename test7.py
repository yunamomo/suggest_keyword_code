# -*- coding: utf-8 -*-
from SPARQLWrapper import SPARQLWrapper

word = "ビートたけし"

uri = '<http://ja.dbpedia.org/resource/{0}>'.format(word)
sparql = SPARQLWrapper(endpoint='http://ja.dbpedia.org/sparql', returnFormat='json')
sparql.setQuery('''
    PREFIX dbpj: <http://ja.dbpedia.org/resource/>
    PREFIX prop: <http://ja.dbpedia.org/property/>
    PREFIX dbp-owl: <http://dbpedia.org/ontology/>
    PREFIX dcterms: <https://dublincore.org/specifications/dublin-core/dcmi-terms/#>
    
    SELECT ?birth_date WHERE
    {{
    {0} dbp-owl:birthDate ?birth_date.
    }}LIMIT 5
'''.format(uri))

results = sparql.query().convert()

print(results)



