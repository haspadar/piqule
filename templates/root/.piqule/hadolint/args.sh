git ls-files -- \
<< config(hadolint.patterns)
   |default(["Dockerfile*"])
   |format("'%s'")
   |join(" \\\n  ")
>> \
<< config(hadolint.ignore)
   |default(["vendor/**","node_modules/**",".git/**"])
   |format("':!:%s'")
   |join(" \\\n  ")
>>
