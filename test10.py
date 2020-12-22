# -*- coding: utf-8 -*-
from SPARQLWrapper import JSON, SPARQLWrapper
import dbpediaknowledge
import json
import sys

args = sys.argv
word = args[1]

sparql = SPARQLWrapper('http://ja.dbpedia.org/sparql')
sparql.setReturnFormat(JSON)

sparql = SPARQLWrapper(endpoint='http://ja.dbpedia.org/sparql', returnFormat='json')
sparql.setQuery("""
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        select distinct ?label where {{
        ?a rdfs:label ?label.
        FILTER (regex (?label, "^{0} ") || (regex (?label, "^{0}") && regex (?label, "{0}$")))
    }}LIMIT 5
    """.format(word))
results = sparql.query().convert()

results_3 = json.dumps(results["results"]["bindings"],ensure_ascii=False)
results_4 = results_3.replace('{"label": {"type": "literal", "xml:lang": "ja", "value": "', '')
results_5 = results_4.replace('"}}', '')
results_7 = results_5.replace('[', '')
results_8 = results_7.replace(']', '')# ここまで文字削除
results_6 = results_8.split(', ')

for item in results_6:
    print(item)