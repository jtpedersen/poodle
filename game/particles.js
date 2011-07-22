// target frames per second
const FPS = 30;
var canvas = null;
var context2D = null;
var frame = 0;
var MAXLIFE = 150;
var MAXSPEED = 3.5;
var mysprite;
var URL = false  ? "http://rotand.dk/tmp/g/" : "http://localhost/game/";
URL = "http://poodle/game/"
var FOREGROUND = 2;

function rand() {
    return 1.0 - 2 * Math.random();
}



//sprite
function sprite(image , x, y) {
    this.img = image;
    this.sprite_width = this.img.width/x;
    this.sprite_height = this.img.height/y;
    this.pos = new Vector(200, 200);
    this.frame = new Vector(0,0);
    this.offset = new Vector(0, 0 ) ;
    this.xs = x;
    this.ys = y;
    this.rotation =0;
    this.flipx = false;
    this.flipy = false;
}
sprite.prototype.next_frame = function() {
    this.frame.x++;
    this.frame.x %= this.xs;
} 

sprite.prototype.set_frame = function(i) {
    this.frame.x = i;
}


//p og f er Vector
sprite.prototype.show = function() {
    //context . drawImage(image, sx, sy, sw, sh, dx, dy, dw, dh)
    var sx = (this.offset.x + this.frame.x) * this.sprite_width;
    var sy = (this.offset.y + this.frame.y) * this.sprite_height;
    var dw = // (2.5 + Math.sin(frame/10)) * 
	this.sprite_width;
    var dh = // (2.5 + Math.sin(frame/10)) * 
	this.sprite_height;
//    console.log( "" + sx+ ", " + sy+ ", " + this.sprite_width+ ", " + this.sprite_height+ ", " + this.pos.x+ ", " + this.pos.y+ ", " + dw+ ", " + dh);
    context2D.save(); //save state
    context2D.translate((this.pos.x + this.sprite_width/2), (this.pos.y + this.sprite_height/2));
    context2D.rotate(this.rotation);
    if (this.flipx) {
	context2D.scale(-1.0, 1);
    }
    if (this.flipy) {
	context2D.scale(1.0, -1.0);
    }

    context2D.translate(-(this.pos.x + this.sprite_width/2), -(this.pos.y + this.sprite_height/2));
    context2D.drawImage(this.img, sx, sy, this.sprite_width, this.sprite_height, this.pos.x, this.pos.y, dw, dh);

    context2D.restore(); //restore state

}

function radians(angle) {
    return angle/360 * 2 * Math.PI;
}

// constructor
function particle(pos, vel, acc, life) {
    this.pos = pos ? pos : new Vector(0,0);
    this.vel = vel ? vel : new Vector(0,0);
    this.acc = acc ? acc : new Vector(0,0);
    this.life = life ? life : Math.floor(rand()* MAXLIFE/2 + MAXLIFE/2 );
    this.alive = true;
    this.g = .1;
    this.maxspeed = MAXSPEED;
    //    console.log("Starting speed " + this.vel.len());
}


particle.alive = false;

particle.prototype.tick = function() {
    if (!this.alive) return;
    this.vel.add(this.acc);
    this.gravity();    
    this.drag();
    this.clamp();
    this.pos.add(this.vel);


    this.life--;
    if (this.life < 0) {
	this.die();
    }
}
particle.prototype.die = function() {
    //    console.log("i have died");
    this.alive = false;
}

particle.prototype.bounce = function(normal) {
    //    I' = I-2 * dot(N,I) * N;
    var speed = this.vel.len();
    //    console.log("bounce " + speed);
    normal.scale( this.vel.dot(normal)*2)
    this.vel.sub( normal );
}

// normalized life
particle.prototype.nlife = function()  {
    return this.life / MAXLIFE ;
}

particle.prototype.drag = function() {
    this.vel.scale(.999);
}

particle.prototype.gravity = function () {
    this.vel.y += this.g;
}

particle.prototype.clamp = function() {
    if(this.vel.lensq() > this.maxspeed) { 
	this.vel.normalize();
	this.vel.scale(this.maxspeed);
//	console.log("clamped")
    }

}


var sprite_img = new Image();
sprite_img.src = URL + "cloud.png";

var poodle_img = new Image();
poodle_img.src = URL + "poodle.png";

var rainbow_img = new Image();
rainbow_img.src = URL + "rainbow.png";


function Poodle() {
    this.sprite = new sprite(poodle_img,1,1);
    this.sprite.pos  =  new Vector(canvas.width/2, canvas.height/2);
    this.angle = 0;
    this.particle = new particle(this.sprite.pos.clone(),  Vector(0,0), new Vector(0,0), 1e6);
}


Poodle.prototype.tick = function() {
    this.particle.tick();
    if(! this.particle.alive) {
	//reset
	this.particle.acc = new Vector(0,0);
	this.particle.life = 1e6;
	this.particle.alive = true;
	console.log("reset particle"); 
    }
//    this.sprite.pos = this.particle.pos.clone();
}

Poodle.prototype.show = function() {
    this.sprite.show();
}


Poodle.prototype.rainbowfart = function() {
    console.log("rainbowfart!");
    if(hud.fuel <= 0) return;
    var direction = new Vector(Math.cos(this.sprite.rotation),
			       Math.sin(this.sprite.rotation));
    this.particle.life = 10;
    
    if(this.sprite.flipx) {
	direction.scale(-1);
    }
    
    this.particle.acc = direction;
    this.particle.acc.scale(1.25);
    
    var r_dir = direction.clone();
    r_dir.scale(-1);
    rainbows.fart(r_dir);
    
    hud.fuel -=1;

}

Poodle.prototype.handle_key = function(k) {
    switch(k) {
    case 38: // up get even 
	this.sprite.rotation = 	 0;
	break;

    case 40: //down should flip direction
	this.sprite.flipx = !this.sprite.flipx;
	break;

    case 39: //right
	this.sprite.rotation += .05;
	this.sprite.rotation = Math.min(this.sprite.rotation, Math.PI/3);
	break;

    case 37: //left
	this.sprite.rotation -= .05;
	this.sprite.rotation = Math.max(this.sprite.rotation, -Math.PI/3);
	break;

    case 32: //space
	this.rainbowfart();
	break;


    }
}



var rainbows = null;
var poodle = null;
var clouds = null;
var hud = null;

window.onload = initparticles;


function initparticles()
{
    console.log("starting");
    canvas = document.getElementById('canvas');
    context2D = canvas.getContext('2d');
    setInterval(draw, 1000 / FPS);
    addEventListener("click", handlemouse, false);
    addEventListener("keydown", handle_keyboard, false);
    canvas.height = window.innerHeight;
    canvas.width = window.innerWidth;
    clouds = new cloud_manager();
    poodle = new Poodle();
    rainbows = new Rainbow_manager();
    hud = new HUD();
}   


function handle_keyboard(e) {
//    console.log(e);
    poodle.handle_key(e.keyCode)
}

function handlemouse(e) {
    var x;
    var y;
    if (e.pageX || e.pageY) {
	x = e.pageX;
	y = e.pageY;
    }
    else {
	x = e.clientX + document.body.scrollLeft +
            document.documentElement.scrollLeft;
	y = e.clientY + document.body.scrollTop +
             document.documentElement.scrollTop;
    }
    x -= canvas.offsetLeft;
    y -= canvas.offsetTop;
    

    console.log("clicked " + x + "  " +y + "Angle=" + rot);
}

function draw() {
    frame++;
    context2D.clearRect(0, 0, canvas.width, canvas.height);
    clouds.tick();
    poodle.tick();
    rainbows.tick();
    clouds.show_background();


    rainbows.show();
    poodle.show();


    clouds.show_forground();
    hud.show();

    clouds.set_goal(poodle.particle.pos.clone());
}