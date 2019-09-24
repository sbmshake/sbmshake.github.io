var particles = [];
const maxParticles = 80;
const maxSpeed = 0.3;
const maxDistance = 200;
const size = 10;
let w = window.innerWidth;
let h = 800;
let phrases = ["Kocham Ciebie bardzo", "Kochana Wiki", "Wiedz proszę"];
var phrase;
var t;
function setup(){
  createCanvas(w, h);
  for(var i = 0; i < maxParticles; i++) {
	  particles.push(new Particle(random(0, w), random(0, h)));
  }
  phrase = phrases[Math.floor(Math.random() * phrases.length)];
  
  t = 0;
}
function draw(){
	background(50);
	for(var i = 0; i < maxParticles; i++) {
		particles[i].update();
	}
	for(var i = 0; i < maxParticles; i++) {
		for(var j = i+1; j < maxParticles; j++) {
			let distance = particles[i].position.dist(particles[j].position);
			if(distance < maxDistance){
				let offsetXi = (particles[i].position.x - w / 2) * 0.01;
				let offsetYi = (particles[i].position.y - h / 2) * 0.01;
				let offsetXj = (particles[j].position.x - w / 2) * 0.01;
				let offsetYj = (particles[j].position.y - h / 2) * 0.01;
				strokeWeight(map(distance, 0, maxDistance, 3, 0));
				stroke(255, 0, 50, map(distance, 0.1 * maxDistance, maxDistance, 100, 0));
				line(
				particles[i].position.x - offsetXi, 
				particles[i].position.y - offsetYi, 
				particles[j].position.x - offsetXj, 
				particles[j].position.y - offsetYj
				);
				stroke(40, 255, 255, map(distance, 0.1 * maxDistance, maxDistance, 100, 0));
				line(
				particles[i].position.x + offsetXi, 
				particles[i].position.y + offsetYi, 
				particles[j].position.x + offsetXj, 
				particles[j].position.y + offsetYj
				);
				stroke(255, map(distance, 0.9 * maxDistance, maxDistance, 255, 0));
				line(particles[i].position.x, particles[i].position.y, particles[j].position.x, particles[j].position.y);
			}
		}
	}
	fill(255);
	noStroke();
	for(var i = 0; i < maxParticles; i++) {
		particles[i].show();
	}
	
	t++;
	if(t >= 100){
		t = 0;
		phrase = phrases[Math.floor(Math.random() * phrases.length)];
	}
	strokeWeight(7);
	textSize(100);
	push();
		push();
			translate(width / 2-3, height / 2-3);
			rotate(0.00005 * Math.pow(t-49.6, 3));
			scale(-0.0004*(t+0.6)*(t-99.6));
			stroke(255, 0, 50, 100);
			fill(255, 0, 50, 100);
			textAlign(CENTER);
			text(phrase, 0, 0);
		pop();
		push();
			translate(width / 2+3, height / 2+3);
			rotate(0.00005 * Math.pow(t-50.6, 3));
			scale(-0.0004*(t-0.6)*(t-100.6));
			stroke(40, 255, 255, 100);
			fill(40, 255, 255, 100);
			textAlign(CENTER);
			text(phrase, 0 , 0);
		pop();
		push();
			translate(width / 2, height / 2);
			rotate(0.00005 * Math.pow(t-50, 3));
			scale(-0.0004*(t-0)*(t-100));
			stroke(255);
			fill(255);
			textAlign(CENTER);
			text(phrase, 0, 0);
		pop();
	pop();
}

class Particle {
	constructor(x, y) {
		this.position = createVector(x, y);
		this.velocity = createVector(random(-1, 1), random(-1, 1)).mult(maxSpeed);
	}
	update() {
		this.position.add(this.velocity);
		if(this.position.x > w + maxDistance) {
			this.position.x = 0 - maxDistance;
		}
		if(this.position.x < 0 - maxDistance) {
			this.position.x = w + maxDistance;
		}
		if(this.position.y > h + maxDistance) {
			this.position.y = 0 - maxDistance;
		}
		if(this.position.y < 0 - maxDistance) {
			this.position.y = h + maxDistance;
		}
	}
	show() {
		let offsetX = (this.position.x - w / 2) * 0.01;
		let offsetY = (this.position.y - h / 2) * 0.01;
		fill(255, 0, 50, 100);
		ellipse(this.position.x - offsetX, this.position.y - offsetY, size);
		fill(40, 255, 255, 100);
		ellipse(this.position.x + offsetX, this.position.y + offsetY, size);
		fill(255);
		ellipse(this.position.x, this.position.y, size);
	}
}