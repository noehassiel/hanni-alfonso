import { WebHaptics } from 'web-haptics';
window.haptics = new WebHaptics();

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Draggable } from 'gsap/Draggable';
gsap.registerPlugin(ScrollTrigger, Draggable);
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
window.Draggable = Draggable;

import Lenis from 'lenis';
window.Lenis = Lenis;

import * as THREE from 'three';
window.THREE = THREE;
