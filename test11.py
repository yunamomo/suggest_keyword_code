# -*- coding: utf-8 -*-
from SPARQLWrapper import JSON, SPARQLWrapper
import dbpediaknowledge
import json
import sys

args = sys.argv
word = args[1]

#results_2 = dbpediaknowledge.get_partial_match(word)
sparql = SPARQLWrapper('http://ja.dbpedia.org/sparql')
sparql.setReturnFormat(JSON)

sparql = SPARQLWrapper(endpoint='http://ja.dbpedia.org/sparql', returnFormat='json')
sparql.setQuery("""
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        select distinct ?label where {{
        ?a rdfs:label ?label.
        FILTER (regex (?label, "^{0} ") || (regex (?label, "^{0}") && regex (?label, "{0}$")))
    }}
    """.format(word))
results = sparql.query().convert()

results_3 = json.dumps(results["results"]["bindings"],ensure_ascii=False)
results_4 = results_3.replace('{"label": {"type": "literal", "xml:lang": "ja", "value": "', '')
results_5 = results_4.replace('"}}', '')
results_7 = results_5.replace('[', '')
results_8 = results_7.replace(']', '')# ここまで文字削除
results_6 = results_8.split(', ')

for item in results_6:
    if (('(' in item) and ('曖昧さ回避' not in item)) or (' ' not in item):
        print(item)
        category = dbpediaknowledge.get_category(item)
        results_13 = json.dumps(category["results"]["bindings"], ensure_ascii=False)
        results_14 = results_13.replace('{"category": {"type": "uri", "value": "', '')
        results_15 = results_14.replace('"}}', '')
        results_17 = results_15.replace('[', '')
        results_18 = results_17.replace(']', '')  # ここまで文字削除
        results_16 = results_18.split(', ')
        for item_2 in results_16:
            if 'http://dbpedia.org/ontology/' in item_2:
                item_change = item_2.replace('http://dbpedia.org/ontology/', '')
                print(item_change)

#部分一致で出てきたワードを取得ok
#完全一致も同時取得ok
#
#
