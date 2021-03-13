// const headers = {
//     "Content-Type": "application/json",
//     Accept: "application/json",
//     withCredentials: true
// };

export default {
    get(url) {
        return axios.get(url, { withCredentials: true });
    },
    post(url, data) {
        return axios.post(url, data, { withCredentials: true });
    }
};
