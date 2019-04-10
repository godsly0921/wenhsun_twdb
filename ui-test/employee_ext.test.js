const puppeteer = require('puppeteer');

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

test('員工分機-增查修刪', async () => {

    let extNumber = '1111';
    let width = '1280'
    let height = '800';

    const browser = await puppeteer.launch({
        headless: false,
        slowMo: 40,
        defaultViewport: null,
        args: [`--window-size=${ width },${ height }`]
    });

    const page = await browser.newPage();
    await page.on('dialog', async dialog => {
        await delay(1000);
        await dialog.accept();
    });

    await page.goto('http://localhost:8080/admin/login');
    await page.click('input#user_account');
    await page.type('input#user_account', 'kevin');
    await page.click('input#password');
    await page.type('input#password', 'kevin');
    await page.select('body > div > div > div > section > form > div:nth-child(4) > select', '0')
    await page.click('input#login-btn');
    await page.waitForSelector('body');

    await page.goto('http://localhost:8080/employee/extensions');
    await page.click('body > div > div > div.right_col > div > div.page-title > div.title_right > div > div > a > button');
    await page.click('input#ext-number');
    await page.type('input#ext-number', extNumber);
    await page.click('#demo-form2 > div:nth-child(4) > div > button');
    let tdFirstText = await page.$eval(
        '#datatable > tbody > tr:first-child td:nth-child(1) a',
        (a) => a.text
    );
    expect(tdFirstText).toBe(extNumber);

    await page.click('#datatable > tbody > tr:first-child td:nth-child(1) a');
    await page.waitForSelector('#modify-btn');
    await page.click('#ext-number');
    await page.type('#ext-number', '7');
    await page.click('#modify-btn');
    await page.waitForSelector('#modify-btn');

    let extNumberInput = await page.$eval(
        '#ext-number',
        (element) => element.value
    );
    expect(extNumberInput).toBe('11117');

    await page.click('#delete-btn');
    await page.waitFor(2000);

    let tdFirstTextAfterDelete = await page.$eval(
        '#datatable > tbody > tr:first-child td:nth-child(1) a',
        (td) => td.text
    );
    expect(tdFirstTextAfterDelete).not.toBe('11117');

    await browser.close();

}, 100000);
