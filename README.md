# Purpose
I have been trying to deploy named entity extraction and constructing linked data from digital-humanities-related data for a couple of years. This repository shares you how to do them. 

# Ogi Nikki
Ogi Nikki is business records of Ogi Domain, called Ogi Han, at Edo era  (1600 - 1868) in Saga, Japan.
Ogi Han was one of feudal domains based on a tax system in Edo era and settled as an affiliation of [Saga Domain](https://en.wikipedia.org/wiki/Saga_Domain).
The number of titles that represents the records is assumed about 100,000. The records contain when, where, who and what happened in Ogi Han. 
The original records are hand-writing, called Kuzushi-ji. The Center for Regional Culture and History, Saga University, has been translating into Japanese texts. The grammar of the texts is called "Sourou bun", quite different from the recent Japanese one. Sourou bun was mainly used for business records and official papers in Edo era.
You can browse the results of the translation at [a digital archive](https://www.dl.saga-u.ac.jp/ogiNikki/) which is also constructed by the center.

# OgiNikki Projects
By 2019, more than 30,000 titles are converted into texts and stored in the archive.
I have tried to convert the data of the archive into [Linked Data](https://www.w3.org/standards/semanticweb/data).

# Merits of Linked Data for Digital Humanities
## Data preservation
I suppose linked data is a future-oriented data. The important merit is that linked data can include information of structured relationships about the data's attributes and annotations, not only the values. This means even if your archives have gone for some reason, you can restore your archives much better and easier again because you don't miss "information" of fields of data.
In other words, when you lose "meanings" of the fields, you lose how to "use" the data. 

## Flexible description of fields and values
Linked Data can also describe relationships on an instance level. You can connect some values in data to other information on the Web. This means you can get new semantic information more than original content.

# Issues of converting data into Linked Data
Technical costs for converting conventional data into Linked Data is the biggest issue.
Lack of technical information and pragmatic use cases of modeling and converting data in Digital Humanities is also an issue.

# Grants
OgiNikki Projects are supported by [JSPS KAKENHI Grant Number 19K20630, 2019-2022](https://kaken.nii.ac.jp/grant/KAKENHI-PROJECT-19K20630/).
