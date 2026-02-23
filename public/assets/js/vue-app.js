// Include Vue.js and Axios for Sina Integration
// This file replicates the CI project's vue-app.js structure

// Load Vue.js and Axios synchronously
if (typeof Vue === 'undefined') {
    document.write('<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"><\/script>');
}
if (typeof axios === 'undefined') {
    document.write('<script src="https://cdn.jsdelivr.net/npm/axios@0.27.2/dist/axios.min.js"><\/script>');
}

// Vue app will be initialized in the view
console.log('Vue.js loaded for Sina integration');
