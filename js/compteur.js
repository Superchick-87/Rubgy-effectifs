  // Envoie une requête HTTP GET à compteur_journalier.php pour obtenir les statistiques de visites
  fetch('compteur_journalier.php')
    .then(res => res.json())  // Transforme la réponse en JSON
    .then(data => {
      // Met à jour l'affichage du total de visites
      document.getElementById('visites-total').textContent = data.total
      // Met à jour l'affichage des visites du jour
      document.getElementById('visites-jour').textContent = data.aujourd_hui
    })
    .catch(err => {
      // En cas d'erreur (fichier manquant, serveur HS...), affiche "N/A"
      console.error('Erreur de compteur :', err)
      document.getElementById('visites-total').textContent = 'N/A'
      document.getElementById('visites-jour').textContent = 'N/A'
    })