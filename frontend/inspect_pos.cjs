const http = require('http');
const { execSync } = require('child_process');

// We can run a small express or node server that loads the page and logs client rects
const puppeteer = require('child_process');
const chromePath = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';

// Let's create an HTML file that fetches /id inside an iframe or directly evaluate via chrome console
