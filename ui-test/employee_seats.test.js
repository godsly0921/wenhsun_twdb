const puppeteer = require('puppeteer');

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

test('員工座位-增查修刪', async () => {

    let seatNumber = 'H1';
    let seatName = '測試座位';
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

    await page.goto('http://localhost:8080/employee/seats');
    await page.click('body > div > div > div.right_col > div > div.page-title > div.title_right > div > div > a');
    await page.click('input#seat-name');
    await page.type('input#seat-name', seatName);
    await page.click('input#seat-number');
    await page.type('input#seat-number', seatNumber);
    await page.click('#demo-form2 > div:nth-child(5) > div > button');
    let tdFirstText = await page.$eval(
        '#datatable > tbody > tr:first-child td:nth-child(1) a',
        (a) => a.text
    );
    let tdSecondText = await page.$eval(
        '#datatable > tbody > tr:nth-child(1) > td:nth-child(2)',
        (td) => td.innerHTML
    );
    expect(tdFirstText).toBe(seatNumber);
    expect(tdSecondText).toBe(seatName);

    await page.click('#datatable > tbody > tr:first-child td:nth-child(1) a');

    await page.waitForSelector('#demo-form2 > div:nth-child(6) > div > button.btn.btn-primary');
    await page.click('#seat-name');
    await page.type('#seat-name', 'B');
    await page.click('#seat-number');
    await page.type('#seat-number', 'B');
    await page.click('#demo-form2 > div:nth-child(6) > div > button.btn.btn-primary');
    await page.waitForSelector('#demo-form2 > div:nth-child(6) > div > button.btn.btn-primary');

    let seatNameInput = await page.$eval(
        '#seat-name',
        (element) => element.value
    );
    let seatNumberInput = await page.$eval(
        '#seat-number',
        (element) => element.value
    );
    expect(seatNameInput).toBe('測試座位B');
    expect(seatNumberInput).toBe('H1B');

    await page.click('#delete-btn');
    await page.waitFor(2000);

    let tdFirstTextAfterDelete = await page.$eval(
        '#datatable > tbody > tr:first-child td:nth-child(1) a',
        (td) => td.text
    );
    expect(tdFirstTextAfterDelete).not.toBe('H1B');

    await browser.close();

}, 100000);
