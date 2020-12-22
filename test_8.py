# -*- coding: utf-8 -*-
from SPARQLWrapper import SPARQLWrapper
import io, sys
import re
import json

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding="utf-8")
sys.stdin = io.TextIOWrapper(sys.stdin.buffer, encoding="utf-8")


args = sys.argv
word = args[1]
word_2 = args[2]

sparql = SPARQLWrapper(endpoint='http://ja.dbpedia.org/sparql', returnFormat='json')
sparql.setQuery("""
    PREFIX resource: <http://ja.dbpedia.org/resource/>
    PREFIX ontology: <http://dbpedia.org/ontology/>
    PREFIX property: <http://ja.dbpedia.org/property/>
    select distinct ?o where {{
    {{
    resource:{0} ontology:{1} ?o .
    }}
    UNION
    {{
    resource:{0} property:{1} ?o .
    }}
    }}
""".format(word, word_2))

results_2 = sparql.query().convert()
results_4 = json.dumps(results_2["results"]["bindings"],ensure_ascii=False)
results_4 = results_4.replace('{"o": {"type": "typed-literal", "datatype": "http://www.w3.org/2001/XMLSchema#double", "value": "', '')
results_4 = results_4.replace('{"o": {"type": "typed-literal", "datatype": "http://www.w3.org/2001/XMLSchema#date", "value": "', '')
results_4 = results_4.replace('{"o": {"type": "uri", "value": "http://ja.dbpedia.org/resource/', '')
results_4 = results_4.replace('{"o": {"type": "literal", "xml:lang": "ja", "value": "', '')
results_4 = results_4.replace('{"o": {"type": "typed-literal", "datatype": "http://dbpedia.org/datatype/centimetre", "value": "', '')
results_5 = results_4.replace('"}}', '')
results_7 = results_5.replace('[', '')
results_8 = results_7.replace(']', '')# ここまで文字削除
results_6 = results_8.split(', ')

for item in results_6:
    print(item)

#人物の完全一致＋年齢や出生地などのサジェストキーワードの一致で検索結果を表示