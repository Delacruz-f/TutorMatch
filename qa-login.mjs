export default async function run(page, ui) {
    await page.fill('input[name="email"]', "admin@tutormatch.test");
    await page.fill('input[name="password"]', "Admin12345");
    await page.click('button[type="submit"]');
    await page.waitForLoadState("networkidle");
    return { url: page.url(), title: await page.title() };
}
