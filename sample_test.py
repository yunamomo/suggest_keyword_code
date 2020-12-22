# -*- coding: utf-8 -*-
from SPARQLWrapper import JSON, SPARQLWrapper
import dbpediaknowledge
import json
import sys
import csv
import pprint
import re

args = sys.argv
word = args[1]

text_2 = re.sub(r"[,.!?:;'()/{}]", " ", word)
text_2 = text_2.replace('_', ' ')
text_2 = text_2.replace('  ', ' ')
text_2 = text_2.replace('  ', ' ')
text_2 = text_2.replace('  ', ' ')
text_4 = text_2.split(' ')
length = len(text_4)

for num in range(length):
    print(text_4[num])

sparql = SPARQLWrapper('http://ja.dbpedia.org/sparql')
sparql.setReturnFormat(JSON)

if length == 1:
    sparql.setQuery("""
        PREFIX subject: <http://purl.org/dc/terms/>
        select distinct ?p ?o where {{
        ?a subject:subject ?o .
        FILTER ((regex (?a, "{0}")) && (regex (?a, "http://ja.dbpedia.org/resource/")))
        }}
    """.format(text_4[0]))

if length == 2:
    sparql.setQuery("""
        PREFIX subject: <http://purl.org/dc/terms/>
        select distinct ?p ?o where {{
        ?a subject:subject ?o .
        FILTER ((regex (?a, "{0}")) && (regex (?a, "{1}")) && (regex (?a, "http://ja.dbpedia.org/resource/")))
        }}
    """.format(text_4[0], text_4[1]))

if length == 3:
    sparql.setQuery("""
        PREFIX subject: <http://purl.org/dc/terms/>
        select distinct ?p ?o where {{
        ?a subject:subject ?o .
        FILTER ((regex (?a, "{0}")) && (regex (?a, "{1}")) && (regex (?a, "{2}")) && (regex (?a, "http://ja.dbpedia.org/resource/")))
        }}
    """.format(text_4[0], text_4[1], text_4[2]))

if length == 4:
    sparql.setQuery("""
        PREFIX subject: <http://purl.org/dc/terms/>
        select distinct ?p ?o where {{
        ?a subject:subject ?o .
        FILTER ((regex (?a, "{0}")) && (regex (?a, "{1}")) && (regex (?a, "{2}")) && (regex (?a, "{3}")) && (regex (?a, "http://ja.dbpedia.org/resource/")))
        }}
    """.format(text_4[0], text_4[1], text_4[2], text_4[3]))

if length == 5:
    sparql.setQuery("""
        PREFIX subject: <http://purl.org/dc/terms/>
        select distinct ?p ?o where {{
        ?a subject:subject ?o .
        FILTER ((regex (?a, "{0}")) && (regex (?a, "{1}")) && (regex (?a, "{2}")) && (regex (?a, "{3}")) && (regex (?a, "{4}")) && (regex (?a, "http://ja.dbpedia.org/resource/")))
        }}
    """.format(text_4[0], text_4[1], text_4[2], text_4[3], text_4[4]))

if length == 6:
    sparql.setQuery("""
        PREFIX subject: <http://purl.org/dc/terms/>
        select distinct ?p ?o where {{
        ?a subject:subject ?o .
        FILTER ((regex (?a, "{0}")) && (regex (?a, "{1}")) && (regex (?a, "{2}")) && (regex (?a, "{3}")) && (regex (?a, "{4}")) && (regex (?a, "{5}")) && (regex (?a, "http://ja.dbpedia.org/resource/")))
        }}
    """.format(text_4[0], text_4[1], text_4[2], text_4[3], text_4[4], text_4[5]))


results_2 = sparql.query().convert()
results_4 = json.dumps(results_2["results"]["bindings"],ensure_ascii=False)
results_4 = results_4.replace('{"label": {"type": "literal", "xml:lang": "ja", "value": "', '')
results_4 = results_4.replace('{"o": {"type": "uri", "value": "http://ja.dbpedia.org/resource/Category:', '')
results_4 = results_4.replace('"}}', '')
results_4 = results_4.replace('[', '')
results_4 = results_4.replace(']', '')# ここまで文字削除
results_6 = results_4.split(', ')

for item in results_6:
    print(item)

#カテゴリ抽出
#普通はエラーが出る文字でも検索可能に