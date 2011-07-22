var HUDborder = 10;

function HUD() {
    this.fuel = 300;
    this.points = 0;
    this.gradient = context2D.createLinearGradient(0,0,100,8);
    this.gradient.addColorStop(0, "#FF0000");
    this.gradient.addColorStop(0.25, "#FF0000");
    this.gradient.addColorStop(1, "#00FF00");
}

HUD.prototype.show = function() {
    context2D.fillStyle = this.gradient;
    context2D.fillRect(HUDborder, HUDborder, HUDborder+this.fuel, HUDborder+4);

    
    context2D.font         = 'italic 12px sans-serif';
    context2D.textBaseline = 'top';

    context2D.fillStyle = "#000000";
    context2D.fillText  (' ' + this.fuel , HUDborder+2, HUDborder+2);


    context2D.font         = 'italic 17px sans-serif';
    context2D.fillText  ('points: ' + this.points , HUDborder, HUDborder + 3 +19);


}