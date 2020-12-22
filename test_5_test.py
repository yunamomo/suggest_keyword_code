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
    select distinct * where {{
    {{
    resource:{0} ?p ?o . 
    FILTER regex (?o, "{1}")
    }}
    UNION
    {{
    resource:{1} ?p ?o . 
    FILTER regex (?o, "{0}")
    }}
    UNION
    {{
    ?s ?p  resource:{0}. 
    FILTER regex (?s, "{1}")
    }}
    UNION
    {{
    ?s ?p resource:{1} . 
    FILTER regex (?s, "{0}")
    }}
    }}
""".format(word, word_2))

#人物＋ドラマの時、後半だと何もデータが取ってこれない
#http://ja.dbpedia.org/resource/山田太郎ものがたり＋櫻井翔を含むだと何もない
#逆もして「出演者」「starring」があればサジェストキーワードにPersonならcategoryに「出演者」、TVshowなら「出演番組」と入れる

results_2 = sparql.query().convert()
results_4 = json.dumps(results_2["results"]["bindings"],ensure_ascii=False)
results_4 = results_4.replace('{"p": {"type": "uri", "value": "http://dbpedia.org/ontology/', '')
results_4 = results_4.replace('{"p": {"type": "uri", "value": "http://ja.dbpedia.org/property/', '')
results_4 = results_4.replace('"s": {"type": "uri", "value": "http://ja.dbpedia.org/resource/', '')
results_4 = results_4.replace('"}}', '')
results_4 = results_4.replace('"}', '')
results_4 = results_4.replace('[', '')
results_4 = results_4.replace(']', '')# ここまで文字削除
results_4 = results_4.replace('\n', '')# ここまで文字削除
results_6 = results_4.split(', ')

for item in results_6:
    #if 'wikiPage' not in item:
        print(item)

#人物＋ドラマで取得
#ドラマ＋人物でも取得
#完全一致＋部分一致
#notableWorkはドラマか映画かバラエティ番組か音楽

#pだけじゃなく全部表示
