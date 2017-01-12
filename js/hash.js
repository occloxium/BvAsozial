function hash(type){
  this.jsSHA = new jsSHA(type, "TEXT");
  this.update = function(text){
    this.jsSHA.update(text);
    return this;
  };
  this.getHash = function(){
    return this.jsSHA.getHash("HEX");
  }
  return this;
}
