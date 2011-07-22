var CLOUDMARGIN = 100;


function cloud(z) {
    this.sprite = new sprite(sprite_img, 2, 1);
    this.zindex =z;
    this.offset = new Vector(this.zindex, -(this.zindex));
    this.offset.scale(5);
}

cloud.prototype.shaddow = function() {
    // the shaddow
    this.sprite.pos.sub(this.offset);
    this.sprite.set_frame(1)
    this.sprite.show();
}


cloud.prototype.show = function() {
    // the cloud
    this.sprite.pos.add(this.offset);
    this.sprite.set_frame(0)
    this.sprite.show();
}

function cloud_manager() {
    this.count = 14;
    this.clouds = [];
    this.center = new Vector(canvas.width/2, canvas.height/2);
    this.goal = new Vector(canvas.width/2, canvas.height/2);
    for(var i =0 ; i < this.count; i++) {
	this.clouds.push(new cloud(.5 + FOREGROUND + rand() * FOREGROUND ));
	this.clouds[i].sprite.pos.x = canvas.width * Math.random();
	this.clouds[i].sprite.pos.y = canvas.height * Math.random();
   }

    //sort by zindex
    this.clouds.sort( function(a,b) {
	return a.zindex > b.zindex;
    });


}

cloud_manager.prototype.set_goal = function(g) {
    this.goal = g;
}


cloud_manager.prototype.show_forground = function() {

    for(var i =0 ; i < this.count; i++) 
    	if (this.clouds[i].zindex >= FOREGROUND)
    	    this.clouds[i].show();	    
}

cloud_manager.prototype.show_background = function() {
    for(var i =0 ; i < this.count; i++) 
    	this.clouds[i].shaddow();

    for(var i =0 ; i < this.count; i++)
	if (this.clouds[i].zindex < FOREGROUND)
    	    this.clouds[i].show(); 
}



cloud_manager.prototype.tick = function() {
    //offset
    var offset = this.center.Sub(this.goal);
    var dist = offset.len();

    // console.log(this.center + " " + this.goal + "=> " + offset );
    
    // offset.normalize();
    // offset.scale(1);

    for(var i =0 ; i < this.count; i++) {
	var tmp = this.clouds[i];
	var scaled_offset = offset.clone();
	scaled_offset.scale(tmp.zindex);

	tmp.sprite.pos.add(scaled_offset);

	// tmp.sprite.pos.x -= tmp.zindex;
	if (tmp.sprite.pos.x > canvas.width + CLOUDMARGIN) tmp.sprite.pos.x = 0;
	if (tmp.sprite.pos.x + CLOUDMARGIN < 0 ) tmp.sprite.pos.x = canvas.width;
	if (tmp.sprite.pos.y > canvas.height + CLOUDMARGIN) tmp.sprite.pos.y = 0;
	if (tmp.sprite.pos.y + CLOUDMARGIN< 0 ) tmp.sprite.pos.y = canvas.height;

    }
    this.center.sub(offset);
}

console.log("clouds are loaded");