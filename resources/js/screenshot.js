const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
  const browser = await puppeteer.launch({ headless: true, args: ['--no-sandbox'] });
  const page = await browser.newPage();
  await page.setContent(fs.readFileSync('report.html', 'utf8'));
  await page.setViewport({ width: 200, height: 270 });
  await page.screenshot({ path: 'public/image/body_image.png' });
  await browser.close();
})();