import csv
import pprint
import sys

args = sys.argv
word = args[1]

with open('category.csv') as f:
    reader = csv.reader(f)
    for row in reader:
        if row[1] == word:
            print(row[0])

#カテゴリ側を呼び出す
#例：Personを呼び出した時に、次に絞るためにPersonを親にもつカテゴリを表示

