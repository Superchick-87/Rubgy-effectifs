(function () {
  // Enregistre l'heure de début de la visite
  let debut = Date.now()
  let intervaleId         // ID de l’intervalle régulier
  let dejaEnvoye = false  // Pour éviter d’envoyer plusieurs fois les données

  // Fonction pour envoyer la durée passée sur le site
  function envoyerTemps(final = false) {
    if (dejaEnvoye) return         // Si déjà envoyé, on sort
    dejaEnvoye = true              // On marque comme envoyé

    const duree = Math.round((Date.now() - debut) / 1000)  // Temps passé en secondes

    if (duree < 2) return          // Ignore si visite trop courte

    // Envoie des données via Beacon API (permet l’envoi même si la page se ferme)
    navigator.sendBeacon('log_temps.php', JSON.stringify({
      datetime: new Date().toISOString(),  // Date/heure d'envoi
      duree_secondes: duree,               // Temps passé
      ip: null                             // IP non fournie ici
    }))

    // Nettoie l'intervalle si c’est la fin
    if (final && intervaleId) clearInterval(intervaleId)
  }

  // Envoie les données juste avant que la page soit fermée
  window.addEventListener('beforeunload', () => envoyerTemps(true))

  // Envoie aussi si l’utilisateur change d’onglet ou minimise (devient inactif)
  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'hidden') envoyerTemps(true)
  })

  // Envoie automatiquement les données toutes les 5 minutes (sécurité)
  intervaleId = setInterval(() => envoyerTemps(false), 300000)
})()