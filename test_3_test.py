# -*- coding: utf-8 -*-
from SPARQLWrapper import JSON, SPARQLWrapper
import dbpediaknowledge
import json
import sys
import csv
import pprint

args = sys.argv
word = args[1]

sparql = SPARQLWrapper('http://ja.dbpedia.org/sparql')
sparql.setReturnFormat(JSON)
sparql.setQuery("""
            PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX resource: <http://ja.dbpedia.org/resource/>
            select distinct ?category where {{
            ?a <http://purl.org/dc/terms/subject> ?category .
            FILTER (regex (?a, "{0}"))
            }}
            """.format(word))
category = sparql.query().convert()
results_2 = json.dumps(category["results"]["bindings"], ensure_ascii=False)
results_2 = results_2.replace('{"category": {"type": "uri", "value": "', '')
results_2 = results_2.replace('"}}', '')
results_2 = results_2.replace('[', '')
results_2 = results_2.replace(']', '')  # ここまで文字削除
results_16 = results_2.split(', ')
for item_2 in results_16:
    if 'http://ja.dbpedia.org/resource/Category:' in item_2:
        item_change = item_2.replace('http://ja.dbpedia.org/resource/Category:', '')
        print(item_change)

#wordを含む単語の全てのカテゴリを取得
#Thingのみ表示
#カテゴリを選択
#sample_2.pyで呼び出し、表示
#子クラスのカテゴリを表示、選択