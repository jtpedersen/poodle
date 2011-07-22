
////////////////////////////////////////
//// RAINBOW
////////////////////////////////////////

function Rainbow(vel) {
    var acc = vel.clone();
    acc.normalize();
    var offset = acc.clone();
    //make sure that the originate from the backend of the poodle
    offset.scale(46);
    acc.scale(.5);
    this.sprite = new sprite(rainbow_img, 6,1);
    this.sprite.pos  =  new Vector(canvas.width/2, canvas.height/2);
    this.sprite.pos.add(offset);
    this.particle = new particle(this.sprite.pos.clone(), vel, acc, 500);
    this.particle.maxspeed = 50;
    this.particle.g = 0;
}

Rainbow.prototype.tick = function() {
    this.particle.tick();
    this.sprite.pos = this.particle.pos;
    if (this.particle.life%4 == 0) {
	this.sprite.next_frame();
    }
    this.sprite.rotation = this.particle.vel.angle();

}

Rainbow.prototype.show = function() {

    this.sprite.show();
}

function Rainbow_manager() {
    this.rainbows = [];
    this.farts = 10;
}


Rainbow_manager.prototype.fart = function(vel) {
    var speed = vel.len();
    var angle = vel.angle();
    for(var i =0 ; i < this.farts; i++) {

	var rainbow_vel = new Vector(0,0);
	var rainbow_speed = 1 + 4.5 * Math.random(); // [0.5; 2.0]
	var rainbow_angle = angle + rand()/Math.PI ;//- this.farts/2 + i * this.farts/2;
//	console.log('rainbow_angle = ' + rainbow_angle);
	rainbow_speed *= speed;
	rainbow_vel.x = rainbow_speed * Math.cos(rainbow_angle);
	rainbow_vel.y = rainbow_speed * Math.sin(rainbow_angle);
//  	r.particle.acc.scale(Math.random());
	var r = new Rainbow(rainbow_vel);
	r.sprite.flipy = rand()>0;
	this.rainbows.push(r);
    }



}

Rainbow_manager.prototype.tick = function() {
    var alive = []
    for(var i =0 ; i < this.rainbows.length; i++) {
	var r = this.rainbows[i];
	  r.tick();
	if (r.particle.alive)
	    alive.push(r);
    }
    this.rainbows = alive;
    
}

Rainbow_manager.prototype.show = function() {
    for(var i =0 ; i < this.rainbows.length; i++) 
	this.rainbows[i].show();
}

////////////////////////////////////////
//// RAINBOW END
////////////////////////////////////////
