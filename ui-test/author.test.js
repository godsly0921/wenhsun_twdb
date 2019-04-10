const puppeteer = require('puppeteer');

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

test('作家-增查修刪', async () => {

    let width = '1280'
    let height = '800';

    const browser = await puppeteer.launch({
        headless: false,
        slowMo: 20,
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

    await page.goto('http://localhost:8080/author');
    await page.click('#new-btn');
    await page.type('#pen_name', '筆名1');
    await page.type('#author_name', "基");
    await page.select('#gender', "M");
    await page.type('#birth', "2019/01/01");
    await page.type('#death', "2100/01/01");
    await page.type('#office_address', "地址1");
    await page.type('#service', "服務單位");
    await page.type('#job_title', "工程師");
    await page.type('#office_phone', "(02)111-8888");
    await page.type('#office_fax', "(02)111-8888");
    await page.type('#home_address', "地址1");
    await page.type('#home_phone', "(02)111-8888");
    await page.type('#home_fax', "(02)111-8888");
    await page.type('#mobile', "0912445678");
    await page.select('#identity_type', "作家");
    await page.type('#social_account', "facebook");
    await page.type('#memo', "不知道要說什麼");
    await page.type('#nationality', "TW");
    await page.type('#identity_number', "99999999");
    await page.type('#residence_address', "地址");
    await page.click('#create-btn');
    let tdFirstText = await page.$eval(
        '#datatable > tbody > tr:first-child td:nth-child(1) a',
        (a) => a.text
    );
    expect(tdFirstText).toBe('基');

    await page.click('#datatable > tbody > tr:first-child td:nth-child(1) a');
    await page.waitForSelector('#modify-btn');
    await page.type('#pen_name', '筆名2;');
    await page.type('#author_name', "小");
    await page.select('#gender', "F");
    await page.type('#office_address', "地址2;");
    await page.evaluate(() => document.getElementById('birth').value="");
    await page.type('#birth', "2019-01-02");
    await page.evaluate(() => document.getElementById('death').value="");
    await page.type('#death', "2100-01-02");
    await page.type('#service', "1");
    await page.type('#job_title', "1");
    await page.type('#office_phone', "(02)222-7777;");
    await page.type('#office_fax', "(02)222-7777;");
    await page.type('#home_address', "地址3;");
    await page.type('#home_phone', "(02)222-7777;");
    await page.type('#home_fax', "(02)222-7777;");
    await page.type('#mobile', "0912345678;");
    await page.select('#identity_type', "作家");
    await page.type('#social_account', "ig;");
    await page.type('#memo', "還是");
    await page.type('#nationality', "TW");
    await page.type('#identity_number', "A123456789;");
    await page.type('#residence_address', "1");

    await page.type('#bank_name', "台新銀行");
    await page.type('#bank_code', "812");
    await page.type('#branch_name', "敦南分行");
    await page.type('#branch_code', "1111");
    await page.type('#bank_account', "1234567890123456");
    await page.type('#account_name', "神祕人");

    await page.type('#bank_name2', "玉山銀行");
    await page.type('#bank_code2', "806");
    await page.type('#branch_name2', "士林分行");
    await page.type('#branch_code2', "2222");
    await page.type('#bank_account2', "1234567890123456");
    await page.type('#account_name2', "神祕的人");

    await page.click('#modify-btn');
    await page.waitForSelector('#modify-btn');

    let pen_name = await page.$eval( '#pen_name', (element) => element.value);
    let author_name = await page.$eval( '#author_name', (element) => element.value);
    let gender = await page.$eval( '#gender', (element) => element.value);
    let office_address = await page.$eval( '#office_address', (element) => element.value);
    let birth = await page.$eval( '#birth', (element) => element.value);
    let death = await page.$eval( '#death', (element) => element.value);
    let service = await page.$eval( '#service', (element) => element.value);
    let job_title = await page.$eval( '#job_title', (element) => element.value);
    let office_phone = await page.$eval( '#office_phone', (element) => element.value);
    let office_fax = await page.$eval( '#office_fax', (element) => element.value);
    let home_address = await page.$eval( '#home_address', (element) => element.value);
    let home_phone = await page.$eval( '#home_phone', (element) => element.value);
    let home_fax = await page.$eval( '#home_fax', (element) => element.value);
    let mobile = await page.$eval( '#mobile', (element) => element.value);
    let identity_type = await page.$eval( '#identity_type', (element) => element.value);
    let social_account = await page.$eval( '#social_account', (element) => element.value);
    let memo = await page.$eval( '#memo', (element) => element.value);
    let nationality = await page.$eval( '#nationality', (element) => element.value);
    let identity_number = await page.$eval( '#identity_number', (element) => element.value);
    let residence_address = await page.$eval( '#residence_address', (element) => element.value);

    let bank_name = await page.$eval( '#bank_name', (element) => element.value);
    let bank_code = await page.$eval( '#bank_code', (element) => element.value);
    let branch_name = await page.$eval( '#branch_name', (element) => element.value);
    let branch_code = await page.$eval( '#branch_code', (element) => element.value);
    let bank_account = await page.$eval( '#bank_account', (element) => element.value);
    let account_name = await page.$eval( '#account_name', (element) => element.value);

    let bank_name2 = await page.$eval( '#bank_name2', (element) => element.value);
    let bank_code2 = await page.$eval( '#bank_code2', (element) => element.value);
    let branch_name2 = await page.$eval( '#branch_name2', (element) => element.value);
    let branch_code2 = await page.$eval( '#branch_code2', (element) => element.value);
    let bank_account2 = await page.$eval( '#bank_account2', (element) => element.value);
    let account_name2 = await page.$eval( '#account_name2', (element) => element.value);

    expect(pen_name).toBe('筆名2;筆名1');
    expect(author_name).toBe('小基');
    expect(gender).toBe('F');
    expect(office_address).toBe('地址2;地址1');
    expect(birth).toBe('2019-01-02');
    expect(death).toBe('2100-01-02');
    expect(service).toBe('1服務單位');
    expect(job_title).toBe('1工程師');
    expect(office_phone).toBe('(02)222-7777;(02)111-8888');
    expect(office_fax).toBe('(02)222-7777;(02)111-8888');
    expect(home_address).toBe('地址3;地址1');
    expect(home_phone).toBe('(02)222-7777;(02)111-8888');
    expect(home_fax).toBe('(02)222-7777;(02)111-8888');
    expect(mobile).toBe('0912345678;0912445678');
    expect(identity_type).toBe('作家');
    expect(social_account).toBe('ig;facebook');
    expect(memo).toBe('還是不知道要說什麼');
    expect(nationality).toBe('TWTW');
    expect(identity_number).toBe('A123456789;99999999');
    expect(residence_address).toBe('1地址');
    expect(bank_name).toBe('台新銀行');
    expect(bank_code).toBe('812');
    expect(branch_name).toBe('敦南分行');
    expect(branch_code).toBe('1111');
    expect(bank_account).toBe('1234567890123456');
    expect(account_name).toBe('神祕人');
    expect(bank_name2).toBe('玉山銀行');
    expect(bank_code2).toBe('806');
    expect(branch_name2).toBe('士林分行');
    expect(branch_code2).toBe('2222');
    expect(bank_account2).toBe('1234567890123456');
    expect(account_name2).toBe('神祕的人');

    await page.click('#delete-btn');
    await page.waitFor(2000);

    let tdFirstTextAfterDelete = await page.$eval(
        '#datatable > tbody > tr:first-child td:nth-child(1) a',
        (td) => td.text
    );
    expect(tdFirstTextAfterDelete).not.toBe('黃小基');

    await browser.close();

}, 100000);
