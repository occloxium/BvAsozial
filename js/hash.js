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

var hash_password = function(form){
	var password = form.find("#password").val();
	form.find("#password").val(new hash('SHA-384').update(password).getHash());
}
