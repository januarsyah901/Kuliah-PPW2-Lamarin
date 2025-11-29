// playwright-api-test.js - Script untuk testing dan screenshot API

import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';

const BASE_URL = 'http://localhost:8000';
const API_URL = `${BASE_URL}/api`;
let authToken = '';
let adminToken = '';
let jobId = 1;
let appId = 1;

// Buat folder screenshots jika belum ada
const screenshotDir = 'screenshots/api-testing';
if (!fs.existsSync(screenshotDir)) {
  fs.mkdirSync(screenshotDir, { recursive: true });
}

test.describe('Lamarin API Testing', () => {

  test.describe('1. Authentication', () => {

    test('1.1 Register User Applicant', async ({ page }) => {
      const response = await page.request.post(`${API_URL}/register`, {
        data: {
          name: 'John Applicant',
          email: `applicant-${Date.now()}@test.com`,
          password: 'password123'
        }
      });

      expect(response.status()).toBe(201);
      const json = await response.json();
      authToken = json.token;
      console.log('✓ Register applicant successful, token:', authToken.substring(0, 20) + '...');
    });

    test('1.2 Register User Admin', async ({ page }) => {
      const response = await page.request.post(`${API_URL}/register`, {
        data: {
          name: 'Admin User',
          email: `admin-${Date.now()}@test.com`,
          password: 'password123'
        }
      });

      expect(response.status()).toBe(201);
      const json = await response.json();
      adminToken = json.token;

      // Set admin role di database
      // UPDATE users SET role = 'admin' WHERE id = {json.user.id}
      console.log('✓ Register admin successful, token:', adminToken.substring(0, 20) + '...');
    });

    test('1.3 Login User', async ({ page }) => {
      const response = await page.request.post(`${API_URL}/login`, {
        data: {
          email: 'applicant@test.com',
          password: 'password123'
        }
      });

      if (response.status() === 200) {
        const json = await response.json();
        authToken = json.token;
        console.log('✓ Login successful');
      }
    });

    test('1.4 Get My Profile', async ({ page }) => {
      const response = await page.request.get(`${API_URL}/me`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      expect(response.status()).toBe(200);
      const json = await response.json();
      console.log('✓ Get profile successful:', json.name);
    });

    test('1.5 Logout', async ({ page }) => {
      const response = await page.request.post(`${API_URL}/logout`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      expect(response.status()).toBe(200);
      console.log('✓ Logout successful');
    });
  });

  test.describe('2. Jobs Management', () => {

    test('2.1 List Jobs', async ({ page }) => {
      const response = await page.request.get(`${API_URL}/jobs`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      expect(response.status()).toBe(200);
      const json = await response.json();
      console.log('✓ List jobs successful, total:', json.data ? json.data.length : json.length);
    });

    test('2.2 Create Job (Admin)', async ({ page }) => {
      const response = await page.request.post(`${API_URL}/jobs`, {
        headers: {
          'Authorization': `Bearer ${adminToken}`,
          'Content-Type': 'application/json'
        },
        data: {
          title: 'Software Engineer',
          description: 'Develop and maintain software applications',
          department: 'IT'
        }
      });

      expect(response.status()).toBe(201);
      const json = await response.json();
      jobId = json.job.id;
      console.log('✓ Create job successful, ID:', jobId);
    });

    test('2.3 Get Job Detail', async ({ page }) => {
      const response = await page.request.get(`${API_URL}/jobs/${jobId}`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      expect(response.status()).toBe(200);
      const json = await response.json();
      console.log('✓ Get job detail successful:', json.title);
    });

    test('2.4 Update Job (Admin)', async ({ page }) => {
      const response = await page.request.put(`${API_URL}/jobs/${jobId}`, {
        headers: {
          'Authorization': `Bearer ${adminToken}`,
          'Content-Type': 'application/json'
        },
        data: {
          title: 'Senior Software Engineer',
          description: 'Lead development team',
          department: 'IT'
        }
      });

      expect(response.status()).toBe(200);
      console.log('✓ Update job successful');
    });

    test('2.5 Search Jobs with Keyword', async ({ page }) => {
      const response = await page.request.get(`${API_URL}/jobs?keyword=Engineer`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      expect(response.status()).toBe(200);
      const json = await response.json();
      console.log('✓ Search jobs successful, found:', json.data ? json.data.length : 0);
    });

    test('2.6 Pagination Test', async ({ page }) => {
      const response = await page.request.get(`${API_URL}/jobs?page=1&per_page=5`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      expect(response.status()).toBe(200);
      const json = await response.json();
      console.log('✓ Pagination test successful, per_page:', json.per_page);
    });

    test('2.7 Public Jobs (No Token)', async ({ page }) => {
      const response = await page.request.get(`${API_URL}/public/jobs`);

      expect(response.status()).toBe(200);
      const json = await response.json();
      console.log('✓ Public jobs accessible without token');
    });
  });

  test.describe('3. Applications', () => {

    test('3.1 List Applications', async ({ page }) => {
      const response = await page.request.get(`${API_URL}/applications`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      expect(response.status()).toBe(200);
      const json = await response.json();
      console.log('✓ List applications successful');
    });

    test('3.2 Update Application Status (Admin)', async ({ page }) => {
      if (appId > 0) {
        const response = await page.request.patch(`${API_URL}/applications/${appId}/status`, {
          headers: {
            'Authorization': `Bearer ${adminToken}`,
            'Content-Type': 'application/json'
          },
          data: {
            status: 'Accepted'
          }
        });

        if (response.status() === 200) {
          console.log('✓ Update application status successful');
        }
      }
    });
  });

  test.describe('4. Swagger Documentation UI', () => {

    test('4.1 Screenshot Swagger UI', async ({ page }) => {
      await page.goto(`${BASE_URL}/api/documentation`, { waitUntil: 'networkidle' });
      await page.screenshot({ path: `${screenshotDir}/01-swagger-ui.png`, fullPage: true });
      console.log('✓ Screenshot Swagger UI taken');
    });

    test('4.2 Screenshot Auth Endpoints', async ({ page }) => {
      await page.goto(`${BASE_URL}/api/documentation`, { waitUntil: 'networkidle' });
      await page.click('text=Authentication');
      await page.screenshot({ path: `${screenshotDir}/02-auth-endpoints.png`, fullPage: true });
      console.log('✓ Screenshot Auth endpoints taken');
    });

    test('4.3 Screenshot Jobs Endpoints', async ({ page }) => {
      await page.goto(`${BASE_URL}/api/documentation`, { waitUntil: 'networkidle' });
      await page.click('text=Jobs');
      await page.screenshot({ path: `${screenshotDir}/03-jobs-endpoints.png`, fullPage: true });
      console.log('✓ Screenshot Jobs endpoints taken');
    });

    test('4.4 Screenshot Applications Endpoints', async ({ page }) => {
      await page.goto(`${BASE_URL}/api/documentation`, { waitUntil: 'networkidle' });
      await page.click('text=Applications');
      await page.screenshot({ path: `${screenshotDir}/04-applications-endpoints.png`, fullPage: true });
      console.log('✓ Screenshot Applications endpoints taken');
    });
  });

  test.describe('5. API Testing with Postman Collection', () => {

    test('5.1 Verify Postman Collection Exists', async () => {
      const collectionPath = path.join('.', 'Lamarin_API.postman_collection.json');
      expect(fs.existsSync(collectionPath)).toBeTruthy();
      console.log('✓ Postman collection file exists');
    });

    test('5.2 Verify Routes File', async () => {
      const routesPath = path.join('routes', 'api.php');
      expect(fs.existsSync(routesPath)).toBeTruthy();
      console.log('✓ API routes file exists');
    });

    test('5.3 Verify Controllers Created', async () => {
      const controllers = [
        'app/Http/Controllers/Api/AuthController.php',
        'app/Http/Controllers/Api/JobApiController.php',
        'app/Http/Controllers/Api/ApplicationApiController.php'
      ];

      controllers.forEach(controller => {
        expect(fs.existsSync(controller)).toBeTruthy();
      });
      console.log('✓ All API controllers created');
    });
  });

  test.describe('6. Delete Job (Admin)', () => {

    test('6.1 Delete Job', async ({ page }) => {
      if (jobId > 0) {
        const response = await page.request.delete(`${API_URL}/jobs/${jobId}`, {
          headers: {
            'Authorization': `Bearer ${adminToken}`
          }
        });

        if (response.status() === 200) {
          console.log('✓ Delete job successful');
        }
      }
    });
  });
});

