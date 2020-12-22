# -*- coding: utf-8 -*-
import json
import dbpediaknowledge
import sys

args = sys.argv
word = args[1]

if __name__ == '__main__':
    synonyms = dbpediaknowledge.get_synonyms(word)
    print(json.dumps(synonyms, indent=2, ensure_ascii=False))
