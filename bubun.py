# -*- coding: utf-8 -*-
from SPARQLWrapper import SPARQLWrapper
import dbpediaknowledge
import json
import sys

args = sys.argv
word = args[1]
word_2 = args[2]
proper = args[3]

pro = proper.split(',')#list

sparql = SPARQLWrapper(endpoint='http://ja.dbpedia.org/sparql', returnFormat='json')

num = 0
results_4=''

for item in pro:
    sparql.setQuery("""
    PREFIX r: <http://ja.dbpedia.org/property/>
    select distinct ?label where {{
    ?a r:{1} ?label.
    FILTER regex (?a, "{0}")
    }}
    """.format(word, item))
    results_2 = sparql.query().convert()
    results_3 = json.dumps(results_2["results"]["bindings"], ensure_ascii=False)
    results_4 += item
    results_4 += '->'
    results_4 += results_3
    results_4 = results_4.replace('{"label": {"type": "literal", "xml:lang": "ja", "value": "', '')
    results_4 = results_4.replace('{"label": {"type": "typed-literal", "datatype": "http://www.w3.org/2001/XMLSchema#integer", "value": "', '')
    results_4 = results_4.replace('{"label": {"type": "uri", "value": "http://ja.dbpedia.org/resource/', '')
    results_4 = results_4.replace('{"label": {"type": "uri", "value": "http://search.yoshimoto.co.jp/talent_prf/?', '')
    results_4 = results_4.replace('"}}', '')
    results_4 = results_4.replace('[', '')
    results_4 = results_4.replace(']', '')  # ここまで文字削除
    item = ':::' + item
    item += '->'
    results_4 = results_4.replace(', ', item)  # ここまで文字削除
    results_4 += ':::'

results_6 = results_4.split(':::')

for item in results_6:
    if word_2 in item:
        print(item)

#含むものを摘出