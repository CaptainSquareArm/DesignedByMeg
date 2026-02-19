const admin = require('firebase-admin');
const functions = require('firebase-functions');
const cors = require('cors')({ origin: true });

// Initialize Firebase
if (!admin.apps.length) {
  admin.initializeApp();
}

const firestore = admin.firestore();

/**
 * WordPress Content API — serve posts, pages, and site data
 */
exports.wp = functions.https.onRequest(async (req, res) => {
  cors(req, res, async () => {
    try {
      const { action, id, type = 'post', limit = 10, offset = 0 } = req.query;

      // GET /api/wp?action=posts&type=post&limit=10
      if (action === 'posts') {
        const postsQuery = firestore
          .collection('wp_posts')
          .where('post_status', '==', 'publish')
          .where('post_type', '==', type)
          .orderBy('post_date', 'desc')
          .limit(parseInt(limit, 10))
          .offset(parseInt(offset, 10));

        const snapshot = await postsQuery.get();
        const posts = [];
        snapshot.forEach(doc => {
          posts.push({ id: doc.id, ...doc.data() });
        });

        return res.json({
          success: true,
          type,
          posts,
          count: posts.length,
        });
      }

      // GET /api/wp?action=single&id=12&type=page
      if (action === 'single') {
        const doc = await firestore
          .collection('wp_posts')
          .where('ID', '==', parseInt(id, 10))
          .limit(1)
          .get();

        if (doc.empty) {
          return res.status(404).json({ error: 'Post not found' });
        }

        const post = { id: doc.docs[0].id, ...doc.docs[0].data() };
        return res.json({ success: true, post });
      }

      // GET /api/wp?action=homepage
      if (action === 'homepage') {
        const homePageIdDoc = await firestore
          .collection('wp_options')
          .where('option_name', '==', 'page_on_front')
          .limit(1)
          .get();

        if (homePageIdDoc.empty) {
          return res.json({ success: false, message: 'Homepage not configured' });
        }

        const pageId = parseInt(homePageIdDoc.docs[0].data().option_value, 10);
        const pageDoc = await firestore
          .collection('wp_posts')
          .where('ID', '==', pageId)
          .limit(1)
          .get();

        if (pageDoc.empty) {
          return res.status(404).json({ error: 'Homepage not found' });
        }

        const page = { id: pageDoc.docs[0].id, ...pageDoc.docs[0].data() };
        return res.json({ success: true, page });
      }

      // GET /api/wp?action=site_info
      if (action === 'site_info') {
        const siteUrlDoc = await firestore
          .collection('wp_options')
          .where('option_name', '==', 'siteurl')
          .limit(1)
          .get();
        const blogtitleDoc = await firestore
          .collection('wp_options')
          .where('option_name', '==', 'blogname')
          .limit(1)
          .get();

        const info = {
          title: blogtitleDoc.empty ? 'DesignByMeg' : blogtitleDoc.docs[0].data().option_value,
          url: siteUrlDoc.empty ? 'https://designedbymeg-355ff.web.app' : siteUrlDoc.docs[0].data().option_value,
        };
        return res.json({ success: true, info });
      }

      return res.status(400).json({ error: 'Invalid action' });
    } catch (error) {
      console.error(error);
      res.status(500).json({ error: error.message });
    }
  });
});
