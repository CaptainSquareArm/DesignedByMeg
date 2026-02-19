// Main entry point for Cloud Functions
module.exports = {
  wp: require('./wordpress-api').wp,
};
