git ls-files -- \
'Dockerfile*' \
':!:vendor/**' \
  ':!:node_modules/**' \
  ':!:.git/**'
