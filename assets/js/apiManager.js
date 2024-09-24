class APIHandler {
  constructor(baseURL, headers) {
    this.baseURL = baseURL;
    this.headers = headers;
  }

  async get(url) {
    try {
      const response = await fetch(this.baseURL + url, {
        method: "GET",
        headers: this.headers,
      });
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error in GET request:", error);
      throw error;
    }
  }

  async post(url, body) {
    try {
      const response = await fetch(this.baseURL + url, {
        method: "POST",
        headers: this.headers,
        body: JSON.stringify(body),
      });
      const data = await response.json();

      return data;
    } catch (error) {
      console.error("Error in POST request:", error);
      throw error;
    }
  }

  async patch(url, body) {
    try {
      const response = await fetch(this.baseURL + url, {
        method: "PATCH",
        headers: this.headers,
        body: JSON.stringify(body),
      });
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error in PATCH request:", error);
      throw error;
    }
  }

  async delete(url) {
    try {
      const response = await fetch(this.baseURL + url, {
        method: "DELETE",
        headers: this.headers,
      });
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error in DELETE request:", error);
      throw error;
    }
  }
}

const apiBaseURL = "??????";

const apiHeaders = {
  "Content-Type": "application/json",
};
const apiManager = new APIHandler(apiBaseURL, apiHeaders);
