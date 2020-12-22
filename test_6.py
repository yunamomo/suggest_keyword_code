# -*- coding: utf-8 -*-
from SPARQLWrapper import SPARQLWrapper
import sys
import re
import json

args = sys.argv
word = args[1]
word_2 = args[2]

sparql = SPARQLWrapper(endpoint='http://ja.dbpedia.org/sparql', returnFormat='json')
sparql.setQuery("""
    PREFIX resource:  <http://ja.dbpedia.org/resource/>
    select distinct * where {
    ?s ?p ?o .
    FILTER regex (?s, "大野智")
    }LIMIT 10
""")

results_2 = sparql.query().convert()
results_4 = json.dumps(results_2["results"]["bindings"],ensure_ascii=False)
results_4 = results_4.replace('{"label": {"type": "literal", "xml:lang": "ja", "value": "', '')
results_4 = results_4.replace('"}}', '')
results_4 = results_4.replace('[', '')
results_4 = results_4.replace(']', '')# ここまで文字削除
results_6 = results_4.split(', {"s": ')
#results_6 = results_4.split(', ')

count = 0

for item in results_6:
    print(item)
    count += 1

print('\n')
print(count)

#人物＋ドラマで取得
#ドラマ＋人物でも取得
#部分一致＋完全一致

#部分一致＋部分一致：できてない

#select distinct * where { {
#<http://ja.dbpedia.org/resource/山田太郎ものがたり> <http://dbpedia.org/ontology/starring> ?o .
#} UNION {
#<http://ja.dbpedia.org/resource/山田太郎ものがたり> <http://ja.dbpedia.org/property/出演者> ?o .
#}}

#sparql.setQuery("""
#    PREFIX resource:  <http://ja.dbpedia.org/resource/>
#    select distinct * where {
#    ?s ?p ?o .
#    FILTER regex (?s, "http://ja.dbpedia.org/resource/")
#    FILTER regex (?s, "三宅裕司")
#    }LIMIT 20
#""")