
function Vector(x,y) {
    this.x = x; this.y=y;
}

Vector.prototype.add = function(other) {
    this.x += other.x;
    this.y += other.y;
}

Vector.prototype.sub = function(other) {
    this.x -= other.x;
    this.y -= other.y;
}

//imutable version 
Vector.prototype.Add = function(other) {
    var tmp = this.clone();
    tmp.sub(other);
    return tmp;
}

//imutable version 
Vector.prototype.Sub = function(other) {
    var tmp = this.clone();
    tmp.sub(other);
    return tmp;
}


Vector.prototype.scale = function(s) {
    this.x *= s;
    this.y *= s;
}

Vector.prototype.len = function() {
    return Math.sqrt(this.x*this.x + this.y*this.y);
}

Vector.prototype.normalize = function() {
    this.scale(1.0/this.len());
}

Vector.prototype.dot = function(other) {
    return this.x*other.x + this.y * other.y;
}


Vector.prototype.lensq = function(other) {
    return this.x*this.x + this.y*this.y;
}


Vector.prototype.clone = function() {
    return new Vector(this.x, this.y);
}

Vector.prototype.toString = function() {
    return "("+ this.x + ", " + this.y + ")";
}


Vector.prototype.angle = function() {
    return Math.atan2(this.y, this.x);
}


