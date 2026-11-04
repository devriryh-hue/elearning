// app/utils/goal.ts
export const GOAL_STATUS: Record<string, { label: string; cls: string }> = {
  not_started: { label: 'Pas commencé', cls: 'bg-secondary' },
  learning:    { label: 'En apprentissage', cls: 'bg-info' },
  to_review:   { label: 'À revoir', cls: 'bg-warning text-dark' },
  stale:       { label: 'À rafraîchir', cls: 'bg-dark' },
  mastered:    { label: 'Maîtrisé', cls: 'bg-success' },
}

export const goalReason = (l: any): string => {
  switch (l.status) {
    case 'not_started': return 'Jamais fait'
    case 'learning':    return `${l.attempts} / ${l.required} tentatives`
    case 'to_review':   return `Moyenne ${l.recent_avg}% pour une cible de ${l.target}%`
    case 'stale':       return 'Non revu depuis plus de 30 jours'
    default:            return ''
  }
}

export const goalDeadlineText = (d: number | null | undefined): string => {
  if (d === null || d === undefined) return 'Sans délai'
  return d < 0 ? `En retard de ${Math.abs(d)} j` : `${d} j restants`
}